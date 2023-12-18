---
title: Docs with VitePress
editLink: true
---

# Commands

### `omp`

- Oh My PMMP provides omp command to allow you to install, upgrade, remove, etc. plugins from Poggit  
- To use all commands of Oh My PMMP, use `oh-my-pmmp.cmds` permission.

#### Usage

```bash
omp
```

#### Options

| Permission | Options |  |
|---|---|---|
| `oh-my-pmmp.help` | `help, h, -h, --help` | List available commands. |
| `oh-my-pmmp.version` | `version, v, -v, --version` | Print plugin version. |
| `oh-my-pmmp.install` | `install, i, -i, --install` | Install a plugin with a specified version or leave it empty to install the latest one. |
| `oh-my-pmmp.remove` | `remove, r, -r, --remove` | Remove a plugin. |
| `oh-my-pmmp.update` | `update` | Update cached data. |
| `oh-my-pmmp.list` | `list, l, -l, --list` | List available plugins or installed plugins. |
| `oh-my-pmmp.show` | `show, s, -s, --show` | Get details about a plugin (name, version, api, depends, etc.). |
| `oh-my-pmmp.extract` | `extract, e, -e, --extract` | Unphar a plugin |
| `oh-my-pmmp.enable` | `enable` | Enable a disabled plugin |
| `oh-my-pmmp.disable` | `disable` | Disable a plugin |