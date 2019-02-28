# symfony-flex-env-issue

## Summary

`composer dump-env` is not equivalent to `DotEnv::loadEnv` loading.

## Analysis 

This seems to be because [$_ENV](https://github.com/symfony/recipes/blob/d9dc7a8ff5b46d20b1197d14bcf2551a448a8ae1/symfony/framework-bundle/4.2/config/bootstrap.php#L11) is empty during the curl request so is overwritten by values from `.env.local.php`, but `$_SERVER` contained `ANIMAL` already so is not overridden.
Then symfony [symfony favours $_ENV](https://github.com/symfony/symfony/blob/91c5b14d8b6e9016c31d46ad8576ecf0ce836221/src/Symfony/Component/DependencyInjection/EnvVarProcessor.php#L113-L117) over `$_SERVER`.

By contrast, [DotEnv will not override $_ENV](https://github.com/symfony/symfony/blob/91c5b14d8b6e9016c31d46ad8576ecf0ce836221/src/Symfony/Component/Dotenv/Dotenv.php#L126-L131) if `$_SERVER` already contains the value.

## Reproducer

### normal case: ✅
```bash
symfony serve -no-tls
curl http://127.0.0.1:8000/animal
🐍%
```

### override ANIMAL: ✅
```bash
ANIMAL=🦍 symfony serve -no-tls
curl http://127.0.0.1:8000/animal
🦍%
```


### composer dump-env + override ANIMAL: ❌
```bash
composer dump-env dev
ANIMAL=🦍 symfony serve -no-tls
curl http://127.0.0.1:8000/animal
🐍%
```

🦍 is expected but we receive 🐍.