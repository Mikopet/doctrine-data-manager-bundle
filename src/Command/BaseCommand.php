<?php
namespace Mikopet\DoctrineDataManagerBundle\Command;

use Doctrine\ORM\EntityManager;
use hanneskod\classtools\Iterator\ClassIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class BaseCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    protected $em;

    /** @var string */
    protected $type;

    /** @var string */
    public $result = false;

    /** @var Finder */
    public $finder;

    /** @var Serializer */
    public $serializer;

    /** @var SymfonyStyle */
    public $io;

    /**
     * BaseCommand constructor
     *
     * @param EntityManager $em
     * @param string        $type
     */
    public function __construct(EntityManager $em, $type)
    {
        $this->em = $em;
        $this->type = $type;

        parent::__construct();
    }

    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('doctrine:data:' . $this->type)
            ->setDescription(ucfirst($this->type) . ' fixture')
            ->addArgument('fixture', InputArgument::OPTIONAL, "The exact fixture you want to " . $this->type);
    }

    /**
     * Executing the command which dumps/loads the fixtures
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->io = new SymfonyStyle($input, $output);
            $this->finder = new Finder();
            $this->serializer = new Serializer(array(new GetSetMethodNormalizer()));
            $this->result = false;
            $iterator = new ClassIterator($this->finder->in('src/AppBundle/Entity'));

            $fixture = $input->getArgument('fixture') ? ucfirst($input->getArgument('fixture')) : null;
            $this->io->title(ucfirst($this->type) . 'ing fixtures' . ($fixture ? " ($fixture)" : ''));

            $entityClasses = [];
            foreach ($iterator->getClassMap() as $className => $splFileInfo) {
                if (empty($fixture) || "AppBundle\\Entity\\" . $fixture == $className) {
                    $entityClasses[] = $className;
                }
            }

            $this->processEntities($entityClasses);
        } catch (\Exception $exception) {
            throw $exception;
        }

        // Result is not verbose yet. Fails after trying to dump the fixture
        $this->result ? $this->io->success('Done!') : $this->io->error('Fail!');

        return $this->result;
    }

    /**
     * Loads/dumps the entities
     *
     * @param  array $entityClasses
     * @return mixed
     */
    abstract function processEntities($entityClasses);
}
