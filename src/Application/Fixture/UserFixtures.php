<?php

namespace Directory\Application\Fixture;

class UserFixtures
{
    /**
     * La primera (Juan) tiene las características “color de los ojos” y “color del coche”, ambas con valor “azul claro”
     * La segunda (Irene) tiene las características “color de los ojos”, “color de la casa” y “color del coche” con los valores “azulados”, “azul” y “rojo”
     * La tercera (Manuel) tiene únicamente la característica “color de la casa” con un color “naranja”.
     *
     * @return array<int, array<int, array<string, string>|string>>
     */
    public static function sampleData(): array
    {
        return [
            ['Juan', [
                'color de los ojos' => 'azul claro',
                'color del coche' => 'azul claro',
            ]],
            ['Irene', [
                'color de los ojos' => 'azulados',
                'color de la casa' => 'azul',
                'color del coche' => 'rojo',
            ]],
            ['Manuel', [
                'color de la casa' => 'naranja',
            ]],
        ];
    }
}
