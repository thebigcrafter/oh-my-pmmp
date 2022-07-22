<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()->in(__DIR__ . "/src");

$config = new PhpCsFixer\Config();

return $config
	->setRiskyAllowed(true)
	->setRules([
		"array_indentation" => true,
		"array_syntax" => ["syntax" => "short"],
		"binary_operator_spaces" => ["default" => "single_space"],
		"blank_line_after_namespace" => true,
		"blank_line_after_opening_tag" => true,
		"blank_line_before_statement" => ["statements" => ["declare"]],
		"cast_spaces" => ["space" => "single"],
		"concat_space" => ["spacing" => "one"],
		"declare_strict_types" => true,
		"elseif" => true,
		"global_namespace_import" => [
			"import_constants" => true,
			"import_functions" => true,
			"import_classes" => null,
		],
		"indentation_type" => true,
		"no_closing_tag" => true,
		"no_empty_phpdoc" => true,
		"no_extra_blank_lines" => true,
		"no_superfluous_phpdoc_tags" => ["allow_mixed" => true],
		"no_trailing_whitespace" => true,
		"no_trailing_whitespace_in_comment" => true,
		"no_whitespace_in_blank_line" => true,
		"no_unused_imports" => true,
		"ordered_imports" => [
			"imports_order" => ["class", "function", "const"],
			"sort_algorithm" => "alpha",
		],
		"phpdoc_line_span" => [
			"property" => "single",
			"method" => null,
			"const" => null,
		],
		"phpdoc_trim" => true,
		"phpdoc_trim_consecutive_blank_line_separation" => true,
		"return_type_declaration" => ["space_before" => "none"],
		"single_import_per_statement" => true,
		"strict_param" => true,
		"unary_operator_spaces" => true,
	])
	->setFinder($finder)
	->setIndent("\t")
	->setLineEnding("\n");
