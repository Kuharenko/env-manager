# Env Manager

## Overview

Env Manager is a powerful tool for managing environment files across multiple projects. It simplifies the process of switching between different environment configurations, ensuring consistency and reducing the risk of errors.

## Installation

To install Env Manager globally using Composer, run the following command:

```bash
composer global require kukharenko/env-manager
```

Follow after install instructions:
```bash
Your projects directory: /Users/username/projects
Your env-manager directory: /Users/username/env-manager
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

Env Manager provides several options to manage your environment files efficiently:

### Options

- `--target` - Required. Specify the environment name to replace. Example: `ua`
- `--projects` - Optional. List the project names to search, separated by commas. Example: `case-service,dictionary-service,import-service`
- `--restore` - Optional. If set to `1`, projects will revert to their previous `.env` files.

### Example Command

To replace environment files for all projects, run the following command:

```bash
php em --target=ua
```

To replace environment files for the `case-service`, run the following command:

```bash
php em --target=ua --projects=case-service
```

To restore the previous `.env` file for `case-service`, run the following command:

```bash
php em --target=ua --projects=case-service --restore=1
```

## License

Env Manager is open-source software licensed under the [MIT license](LICENSE).

---

By following the above steps, you should be able to easily install and use Env Manager to manage your environment configurations across multiple projects efficiently.