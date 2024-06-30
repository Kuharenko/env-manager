# Env Replacer

## Overview

Env Replacer is a powerful tool for managing environment files across multiple projects. It simplifies the process of switching between different environment configurations, ensuring consistency and reducing the risk of errors.

## Installation

To install Env Replacer globally using Composer, run the following command:

```bash
composer global require kukharenko/env-manager
```
Follow after install instructions:
```bash
cd $(composer global show -P | grep "kukharenko/env-manager" | awk '{print $2}') && composer install
```
### Verify Installation Path

Ensure that `vendor/bin` is added to your `PATH` environment variable. You can check this by running:

```bash
echo $PATH
```

If `vendor/bin` is not included, you need to add it manually:


Edit your `~/.zshrc` and `~/.bash_profile` files:

```bash
vim ~/.zshrc
vim ~/.bash_profile
```

Add the following line:

```bash
export PATH="$PATH:/Users/yourusername/.composer/vendor/bin"
```

### Apply the Changes

After editing the respective files, apply the changes by sourcing them:

```bash
source ~/.zshrc
source ~/.bash_profile
```

## Usage

Env Replacer provides several options to manage your environment files efficiently:

### Options

- `--project` - Specify the environment name to replace. Example: `ua`
- `--service` - List the service names to search, separated by commas. Example: `case-service,dictionary-service,import-service`
- `--restore` - If set to `true`, projects will revert to their previous `.env` files.

### Example Command

To replace environment files for all projects, run the following command:

```bash
php er --project=ua
er ua
```

To replace environment files for the `case-service`, run the following command:

```bash
php er --project=ua --service=case-service
er ua case-service
```

To restore the previous `.env` file for `case-service`, run the following command:

```bash
php er --projects=case-service --restore=true
er --projects=case-service --restore=true
```

## Env files

By default, project `.env` files are stored in the `/Users/yourusername/env-manager/concrete` directory. You can create a new configuration file, such as `test.env`, and then use it as follows:

```sh
php er --project=test
```

or shorter:

```sh
er test
```

This command will replace the existing `.env` file in every service with a merged version of `base.env` and `test.env`.

## License

Env Manager is open-source software licensed under the [MIT license](LICENSE).

---

By following the above steps, you should be able to easily install and use Env Manager to manage your environment configurations across multiple projects efficiently.