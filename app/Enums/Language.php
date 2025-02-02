<?php

namespace App\Enums;

use Illuminate\Support\Str;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self BLADE()
 * @method static self CSS()
 * @method static self GO()
 * @method static self HTML()
 * @method static self JAVASCRIPT()
 * @method static self MARKDOWN()
 * @method static self PHP()
 * @method static self PYTHON()
 * @method static self RUBY()
 * @method static self SCSS()
 * @method static self SHELL()
 * @method static self TYPESCRIPT()
 * @method static self VUE()
 * @method static self DOCKERFILE()
 * @method static self C_SHARP()
 * @method static self JAVA()
 * @method static self COFFEESCRIPT()
 * @method static self SMARTY()
 * @method static self ELIXIR()
 * @method static self POWERSHELL()
 * @method static self YAML()
 * @method static self NOASSERTION()
 */
final class Language extends Enum
{
    public function color(): string
    {
        return $this->equals(static::NOASSERTION(), static::SMARTY()) ? 'gray-300' : Str::slug($this->value);
    }

    protected static function values(): array
    {
        return [
            'C_SHARP' => 'C#',
            'NOASSERTION' => 'OTHER',
        ];
    }

    protected static function labels(): array
    {
        return [
            'BLADE' => 'Blade',
            'GO' => 'Go',
            'JAVASCRIPT' => 'JavaScript',
            'MARKDOWN' => 'Markdown',
            'PHP' => 'PHP',
            'PYTHON' => 'Python',
            'RUBY' => 'Ruby',
            'SHELL' => 'Shell',
            'TYPESCRIPT' => 'Typescript',
            'VUE' => 'Vue.js',
            'DOCKERFILE' => 'Dockerfile',
            'COFFEESCRIPT' => 'CoffeeScript',
            'JAVA' => 'Java',
            'ELIXIR' => 'Elixir',
            'SMARTY' => 'Smarty',
            'POWERSHELL' => 'PowerShell',
            'C_SHARP' => 'C#',
            'NOASSERTION' => 'Other',
        ];
    }
}
