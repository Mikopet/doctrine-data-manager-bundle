<?php
namespace Mikopet\DoctrineDataManager\Command;

use Mikopet\DoctrineDataManager\Fixture;

class LoadCommand extends BaseCommand
{
    /** {@inheritdoc} */
    public function processEntities($entityClasses)
    {
        foreach ($entityClasses as $entityClass) {
            $this->io->text($entityClass);

            $class = new \ReflectionClass($entityClass);
            $data = Fixture::get($class->getShortName());

            $this->io->progressStart(count($data));

            foreach ($data as $d) {
                $entity = new $entityClass;

                foreach ($d as $property => $value) {
                    $method = sprintf('set%s', ucwords($property));
                    if (method_exists($entity, $method)) {
                        $joinedClass = $class->getMethod($method)->getParameters()[0]->getClass();
                        if (is_null($joinedClass)) {
                            $entity->$method($value);
                        } else {
                            $data = $this->em->getRepository($joinedClass->getName())->findOneBy(['id' => $value['id']]);
                            $entity->$method($data);
                        }
                    }
                }

                $this->em->persist($entity);
                $this->io->progressAdvance();
            }

            $this->em->flush();
            $this->io->progressFinish();

            if (!$this->result) {
                break;
            }
        }
    }
}
