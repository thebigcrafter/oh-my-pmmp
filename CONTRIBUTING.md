# Contributing to Oh My PMMP

Welcome, and thank you for your interest in contributing to Oh My PMMP!

# Reporting Issues

Have a problem with Oh My PMMP or a feature request? We want to hear about it!

**NOTE:** Please check if the issue exists before creating.

If you cannot find an existing issue that describes your bug or feature, create a new issue using the guidelines below.

# Writing good bug reports and feature requests

To have a good bug reports and feature requests, please follow these guidelines:
- Only a single issue per issue and feature request.
- The more information you provide, the more likely it is that someone or us will succeed in recreating the problem and finding a fix.

Please include the following with each issue:
- Version of PocketMine.
- Version of PHP.
- Your OS.
- What you expected to see, versus what you actually saw.
- Steps to reproduce the issue.
- Images, logs, or a link to a video showing the issue occurring.

# Translate this plugin

Before translate this plugin, please add the language will be translated to the `AVAILABLE_LANGUAGES` array in [`src/thebigcrafter/omp/constants/Languages.php`](https://github.com/thebigcrafter/oh-my-pmmp/blob/main/src/thebigcrafter/omp/constants/Languages.php#L17)  
Copy the `resources/lang/en_US.json`, rename and translate it.  
**NOTE:** The language name in `AVAILABLE_LANGUAGES` must be the same as the language file name in `resources/lang/`.

For example:
```php
public const AVAILABLE_LANGUAGES = ["en_US"]; # => public const AVAILABLE_LANGUAGES = ["en_US", "vi_VN"];
```
And the `resources/lang` folder will have:
- en_US.json
- vi_VN.json

# Asking Questions

Please join our [Discord server](https://discord.gg/PykBfE2TZ9) to ask questions, get help, and discuss the project.

# Thank You!

Thank you for taking the time to contribute.
