<img src="assets/icon.png" align="left" width="140px" height="140px" />
<img align="left" width="0" height="140px" hspace="10"/>

The asynchronous <a href="https://pmmp.io">PocketMine-MP</a> plugin manager

[![PHPStan](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/phpstan.yml/badge.svg)](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/phpstan.yml)
![GitHub](https://img.shields.io/github/license/thebigcrafter/oh-my-pmmp)

Oh My PMMP provides commands to allow you to install plugins from [Poggit](https"//poggit.pmmp.io). It's fast and easy to use.

<br />

<details>

<summary>Table of Contents</summary>

- [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Basic Installation](#basic-installation)
- [Using Oh My PMMP](#using-oh-my-pmmp)
    - [Commands](#commands)
- [Advanced Topics](#advanced-topics)
    - [Advanced Installation](#advanced-installation)
      - [Build from source](#build-from-source)
      - [Run from source](#run-from-source)
- [How Do I Contribute To Oh My PMMP?](#how-do-i-contribute-to-oh-my-pmmp)
- [License](#license)

</details>

# Getting Started

## Prerequisites

- [PHP binary for PocketMine-MP](https://jenkins.pmmp.io/job/PHP-8.0-Aggregate/) 8.0+
- [PocketMine-MP](https://pmmp.io) v4.3.4 or higher.

## Basic installation

1. Download the latest version of the plugin from [here](https://github.com/thebigcrafter/oh-my-pmmp/releases).
2. Put it in the plugins folder.
3. Restart your server.

# Using Oh My PMMP

## Commands

| Commands                           | Description      |
| ---------------------------------- | ---------------- |
| /install \<plugin name> \<version> | Install a plugin |
| /remove \<plugin name>             | Remove a plugin  |
| /upgrade \<plugin name>            | Upgrade a plugin |

# Advanced Topics

## Advanced Installation

### Build from source

1. Clone the repository.
```shell
git clone https://github.com/thebigcrafter/oh-my-pmmp
cd oh-my-pmmp
```

2. Download build script.  
**`NOTE:` Make sure `curl` is installed**

```shell
curl -o ../ConsoleScript.php https://raw.githubusercontent.com/pmmp/DevTools/master/src/ConsoleScript.php
```

3. Install dependencies.
```shell
composer install --no-dev
```

4. Make build folder
```shell
mkdir build
```

5. Run the build script.
```shell
php -dphar.readonly=0 ../ConsoleScript.php --make src,plugin.yml --out build/oh-my-pmmp.phar
```

6. Enjoy the phar file in build folder.

### Run from source

1. Download DevTools at [here.](https://poggit.pmmp.io/p/DevTools/) and put it in the plugins folder.

2. Clone the repository.
```shell
cd your_server/plugins/
git clone https://github.com/thebigcrafter/oh-my-pmmp
```

3. Install dependencies.
```shell
composer install --no-dev
```

4. Start your server and enjoy the plugin.

# How do I contribute to Oh My PMMP?

Before you participate in our community, please read the [code of conduct](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/CODE_OF_CONDUCT.md).

See [Contributing](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/CONTRIBUTING.md) for more details.

# License

Licensed under the [GPL-3.0](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/LICENSE) license.
