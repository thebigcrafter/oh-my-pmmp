# Commands

Oh My PMMP provides `omp` command to allow you to install, upgrade, remove, etc. plugins from [Poggit](https://poggit.pmmp.io)  
`NOTE:` To use all commands of Oh My PMMP, use `oh-my-pmmp.cmds` permission.

### /omp help

> Permission: oh-my-pmmp.help

List available commands.

### /omp version

> Permission: oh-my-pmmp.version

Print plugin version.

### /omp install <plugin> <version>

> Permission: oh-my-pmmp.install

Install a plugin with a specified version or leave it empty to install the latest one.

### /omp remove <plugin>

> Permission: oh-my-pmmp.remove

Remove a plugin.

### /omp update

> Permission: oh-my-pmmp.update

Update cached data.

### /omp list

> Permission: oh-my-pmmp.list

List available plugins or installed plugins.

- To display the list of plugins by page, use the command `omp list <page>`, default is `1`.

### /omp show <plugin> <version>

> Permission: oh-my-pmmp.show

Get details about a plugin (name, version, api, depends, etc.).

### /omp extract <plugin>

> Permission: oh-my-pmmp.extract

Unphar a plugin

### /omp enable <plugin>

> Permission: oh-my-pmmp.enable

Enable a disabled plugin

### /omp disable <plugin>

> Permission: oh-my-pmmp.disable

Disable a plugin