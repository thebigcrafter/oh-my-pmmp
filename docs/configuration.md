---
title: Docs with VitePress
editLink: true
---

## Language
Change oh-my-pmmp's language. For available languages, check [here](https://github.com/thebigcrafter/oh-my-pmmp/tree/main/resources/lang).
```yaml
language: en_US
```

## Plugins per page
Limit the numbers of plugins that `list` commands can show for each page.
```yaml
pluginsPerPage: 10
```

## Skip incompatible plugins
Outdated plugins will not be stored in the cache memory (a.k.a. RAM). This can save your server memory.
```yaml
skipIncompatiblePlugins: true # It will skip caching outdated API plugins if it is set to true
```