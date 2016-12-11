<?php
namespace Mikopet\DoctrineDataManager\Command;

use Mikopet\DoctrineDataManager\Fixture;

class DumpCommand extends BaseCommand
{
    /** {@inheritdoc} */
    public function processEntities($entityClasses)
    {
        foreach ($entityClasses as $entityClass) {
            $this->io->text($entityClass);

            $data = $this->em->getRepository($entityClass)->findBy([], ['id' => "ASC"]);
            $this->io->progressStart(count($data));

            $dump = [];
            foreach ($data as $d) {
                $dump[str_replace(" ", "_", strtolower($d))] = $this->serializer->normalize($d);
                $this->io->progressAdvance();
            }

            $class = new \ReflectionClass($entityClass);
            $this->result = Fixture::set($class->getShortName(), $dump);

            $this->io->progressFinish();

            if (!$this->result) {
                break;
            }
        }
    }
}
