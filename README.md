<img src="assets/icon.png" align="left" width="140px" height="140px" />
<img align="left" width="0" height="140px" hspace="10"/>

The asynchronous <a href="https://pmmp.io">PocketMine-MP</a> plugin manager

[![CI](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/ci.yml/badge.svg)](https://github.com/thebigcrafter/oh-my-pmmp/actions/workflows/ci.yml)
[![State](https://poggit.pmmp.io/shield.state/oh-my-pmmp)](https://poggit.pmmp.io/p/oh-my-pmmp)
[![Downloads Total](https://poggit.pmmp.io/shield.dl.total/oh-my-pmmp)](https://poggit.pmmp.io/p/oh-my-pmmp)
[![License](https://img.shields.io/github/license/thebigcrafter/oh-my-pmmp?color=ecebe6&labelColor=bd93f9&logoColor=white)](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/LICENSE)
[![Discord](https://img.shields.io/discord/1087729577004122112?label=&color=ecebe6&labelColor=bd93f9&logo=discord&logoColor=white)](https://discord.gg/PykBfE2TZ9 
)

Oh My PMMP provides commands to allow you to manage plugins. It's fast and easy to use.

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

- [PHP binary for PocketMine-MP](https://github.com/pmmp/PHP-Binaries/releases) 8.1+
- [PocketMine-MP](https://github.com/pmmp/PocketMine-MP/releases) v5.0.0 or higher.

## Basic installation

1. Download the latest version of the plugin from [here](https://github.com/thebigcrafter/oh-my-pmmp/releases).
2. Put it in the plugins folder.
3. Restart your server.

# Using Oh My PMMP

## Commands

Oh My PMMP provides `omp` command to allow you to install, upgrade, remove, etc. plugins from [Poggit](https://poggit.pmmp.io)

#### `omp help`

**`Permission:` `oh-my-pmmp.help`**

List available commands.

#### `omp version`

**`Permission:` `oh-my-pmmp.version`**

Print plugin version.

#### `omp install` `<plugin>` `<version>`

**`Permission:` `oh-my-pmmp.install`**

Install a plugin with a specified version or leave it empty to install the latest one.

#### `omp remove` `<plugin>`

**`Permission:` `oh-my-pmmp.remove`**

Remove a plugin.

#### `omp update`

**`Permission:` `oh-my-pmmp.update`**

Update cached data.

#### `omp list`

**`Permission:` `oh-my-pmmp.list`**

List available plugins or installed plugins.

- To display the list of plugins by page, use the command `omp list <page>`, default is `1`.
- To display all plugins, use the command `omp list --all`.

#### `omp show` `<plugin>` `<version>`

**`Permission:` `oh-my-pmmp.show`**

Get details about a plugin (name, version, api, depends, etc.).

#### `omp extract` `<plugin>`

**`Permission:` `oh-my-pmmp.extract`**

Unphar a plugin

#### `omp enable` `<plugin>`

**`Permission:` `oh-my-pmmp.enable`**

Enable a disabled plugin

#### `omp disable` `<plugin>`

**`Permission:` `oh-my-pmmp.disable`**

Disable a plugin

`NOTE:` To use all commands of Oh My PMMP, use `oh-my-pmmp.cmds` permission.

# How do I contribute to Oh My PMMP?

Before you participate in our community, please read the [Code of Conduct](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/CODE_OF_CONDUCT.md).

See [Contributing](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/CONTRIBUTING.md) for more details.

# Special Thanks To:

<a href="https://www.jetbrains.com/community/opensource/#support"><img src="https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg" alt="JetBrains Logo (Main) logo."><a/>

[JetBrains](https://jetbrains.com), the creator of the PhpStorm, supports oh-my-pmmp with one of their [Open Source Licenses](https://www.jetbrains.com/community/opensource/#support). PhpStorm is the recommended IDE for working with oh-my-pmmp, and most of the thebigcrafter developers is using it.

# License

Licensed under the [GNU General Public License v3.0](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/LICENSE) license.
