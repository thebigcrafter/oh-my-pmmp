<img src="assets/icon.png" align="left" width="140px" height="140px" />
<img align="left" width="0" height="140px" hspace="10"/>

The asynchronous <a href="https://pmmp.io">PocketMine-MP</a> plugin manager

[![PHPStan](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/phpstan.yml/badge.svg)](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/phpstan.yml)
[![Prettier Check](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/prettier.yml/badge.svg)](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/prettier.yml)
[![State](https://poggit.pmmp.io/shield.state/oh-my-pmmp)](https://poggit.pmmp.io/p/oh-my-pmmp)
[![Downloads Total](https://poggit.pmmp.io/shield.dl.total/oh-my-pmmp)](https://poggit.pmmp.io/p/oh-my-pmmp)
[![License](https://img.shields.io/github/license/thebigcrafter/oh-my-pmmp)](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/LICENSE)
[![Discord](https://img.shields.io/discord/970294579372912700?label=discord&color=7289DA&logo=discord)](https://discord.gg/cEXW8uK6QA)

Oh My PMMP provides commands to allow you to install plugins from [Poggit](https"//poggit.pmmp.io). It's fast and easy to use.

<br />

<details>

<summary>Table of Contents</summary>

- [Getting Started](#getting-started)
	- [Prerequisites](#prerequisites)
	- [Basic installation](#basic-installation)
- [Using Oh My PMMP](#using-oh-my-pmmp)
	- [Commands](#commands)
- [Advanced Topics](#advanced-topics)
	- [Developer Mode](#developer-mode)
- [How do I contribute to Oh My PMMP?](#how-do-i-contribute-to-oh-my-pmmp)
- [License](#license)

</details>

# Getting Started

## Prerequisites

- [PHP binary for PocketMine-MP](https://jenkins.pmmp.io/job/PHP-8.0-Aggregate/) 8.0+
- [PocketMine-MP](https://pmmp.io) v4.0.0 or higher.

## Basic installation

1. Download the latest version of the plugin from [here](https://github.com/thebigcrafter/oh-my-pmmp/releases).
2. Put it in the plugins folder.
3. Restart your server.

# Using Oh My PMMP

## Commands

Oh My PMMP provides `omp` command to allow you to install, upgrade, remove, etc. plugins from [Poggit](https"//poggit.pmmp.io)

#### `omp help`

**`Permission:` `oh-my-pmmp.help`**

List available commands.

#### `omp version`

**`Permission:` `oh-my-pmmp.version`**

Print plugin version.

#### `omp install` `<plugin>` `<version>`

**`Permission:` `oh-my-pmmp.install`**

Install a plugin with a specified version or use the word `latest` as a version to install the latest one.

#### `omp remove` `<plugin>`

**`Permission:` `oh-my-pmmp.remove`**

Remove a plugin.

> The plugin won't be disable upon deletion so you need to restart your server to apply changes. We are making it disable.

#### `omp update`

**`Permission:` `oh-my-pmmp.update`**

Update cached data.

#### `omp list`

**`Permission:` `oh-my-pmmp.list`**

List available plugins or installed plugins.

- To list the installed plugins, you can add `i`, `-installed` or `--installed` behind the command, `omp list --installed`, for instance.

#### `omp show` `<plugin>` `<version>`

**`Permission:` `oh-my-pmmp.show`**

Get details about a plugin (name, version, api, depends, etc.).

#### `omp upgrade` `<plugin>` `<version>`

**`Permission:` `oh-my-pmmp.upgrade`**

Upgrade a plugin to a specified version or use the word `latest` as a version to upgrade to the latest one.


`NOTE:` To use all commands of Oh My PMMP, use `oh-my-pmmp.cmds` permission.

# Advanced Topics

## Developer Mode

Turn on this mode in `config.yml` by changing `devMode` to `true`

Some features will be unlocked after developer mode is enabled:
- **Download plugins as folder**: Download the plugin and oh-my-pmmp will help you extract it by adding the word `true` after the install command. For example: `omp i ExamplePlugin latest true`

# How do I contribute to Oh My PMMP?

Before you participate in our community, please read the [Code of Conduct](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/CODE_OF_CONDUCT.md).

See [Contributing](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/CONTRIBUTING.md) for more details.

# License

Licensed under the [GNU General Public License v3.0](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/LICENSE) license.