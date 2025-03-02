<?php

declare(strict_types=1);

namespace GeoffGarit\PhpDotCar;

use \Exception;

/**
*  PhpDotCar Reader class
*
*  @author GeoffGarit
*  @package GeoffGarit\PhpDotCar
*/
class Reader
{
    public function parseCar(string $path): array
    {
        $fileContent = file_get_contents($path);

        $fileContent = str_replace(['do local _={', 'return _', 'end'], '', $fileContent);

        return $this->parseLuaToArray($fileContent);
    }

    private function parseLuaToArray($content): array
    {
        ini_set('pcre.jit', '0');

        $content = trim($content);
        $content = preg_replace('/\s*=\s*/', ':', $content);
        $content = preg_replace('/\["(.*?)"\]/', '"\1"', $content);
        $content = preg_replace('/(\w+):/', '"\1":', $content);
        $content = preg_replace('/,(\s*[}\]])/', '\1', $content);
        $content = str_replace(
            [
                '"Automation":',
                '"https":',
                '"EBodyType":',
            ],
            [
                'Automation:',
                'https:',
                'EBodyType:',
            ],
            $content,
        );
        $content = preg_replace(
            [
                '/\s*\"ImageData.*\n/',
                '/"(FrontEngineBounds|RearEngineBounds|MidEngineBounds|Bones|EnginePoint|Fixtures|GearboxPoint|TrimDealerPricing)"\s*:\s*(\{(?:[^{}]|\{(?:[^{}]|\{[^{}]*\})*\})*\})/',
                '/"PaintParameters":\{(.|\n)*?"InclinationFront"/',
            ],
            [
                '"ImageData":""',
                '"\1":{}',
                '"PaintParameters":{},' . PHP_EOL . '"InclinationFront"',
            ],
            $content,
        );

        $json = '{' . PHP_EOL . $content;

        return json_decode($json, true);
    }
}
