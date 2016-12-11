<?php
namespace Mikopet\DoctrineDataManagerBundle\Fixture;

use Symfony\Component\Yaml\Yaml;

class Fixture
{
    const FIXTURE_PATH = __DIR__ . '/../../fixtures/';

    /**
     * @param   string $fixture
     * @return  array
     */
    private static function load($fixture)
    {
        return Yaml::parse(file_get_contents(self::FIXTURE_PATH . $fixture . '.yml'));
    }

    /**
     * @param   string $fixture
     * @param   array  $data
     * @return  bool
     */
    private static function save($fixture, $data)
    {
        return (bool)file_put_contents(self::FIXTURE_PATH . $fixture . '.yml', Yaml::dump($data));
    }

    /**
     * @param   string $fixture
     * @param   bool   $key
     * @return  array
     */
    public static function get($fixture, $key = false)
    {
        $data = self::load($fixture);

        if ($key) {
            return $data[$key];
        }

        return $data;
    }

    /**
     * @param   string $fixture
     * @param   bool   $key
     * @param   array  $data
     * @return  bool
     */
    public static function set($fixture, $data, $key = false)
    {
        if ($key) {
            $override = self::load($fixture);
            $override[$key] = $data;
            return self::save($fixture, $override);
        }

        return self::save($fixture, $data);
    }
}
