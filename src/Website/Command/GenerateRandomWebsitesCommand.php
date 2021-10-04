<?php

declare(strict_types=1);

namespace App\Website\Command;

use App\Page\Component\AbstractComponent;
use App\Page\Component\BackgroundImage\BackgroundImageComponent;
use App\Page\Component\Html\HtmlComponent;
use App\Page\Component\Image\ImageComponent;
use App\Page\Component\Margins\MarginsComponent;
use App\Page\Component\Margins\MarginSize;
use App\Page\Component\Page\PageComponent;
use App\Page\Component\ThreeColumns\ThreeColumnsComponent;
use App\Page\Component\TwoColumns\TwoColumnsComponent;
use App\User\Entity\Repository\UserRepository;
use App\Website\Entity\Repository\WebsiteRepository;
use App\Website\Entity\Website;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomWebsitesCommand extends Command
{
    protected static $defaultName = 'website:generate-random';
    private WebsiteRepository $websiteRepository;
    private UserRepository $userRepository;

    public function __construct(WebsiteRepository $websiteRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->websiteRepository = $websiteRepository;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this->setDescription('Starts command scheduler')
            ->addOption('domain', 'd', InputOption::VALUE_REQUIRED, 'Domain to generate subdomains')
            ->addOption('owner', 'o', InputOption::VALUE_REQUIRED, 'Owner of websites')
            ->addOption('websites', 'w', InputOption::VALUE_OPTIONAL, 'Count of websites to generate', 1)
            ->addOption('components', 'c', InputOption::VALUE_OPTIONAL, 'Count of components in website', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $domain = (string)$input->getOption('domain');
        $websites = (int)$input->getOption('websites');
        $components = (int)$input->getOption('components');
        $user = $this->userRepository->findOneByEmail((string)$input->getOption('owner'));
        if (empty($user) || empty($domain)) {
            throw new \InvalidArgumentException('User and domain are required');
        }

        for ($i = 0; $i < $websites; ++$i) {
            $website = new Website();
            $website->setOwner($user);
            $website->setUrl($this->randomString(16) . '.' . $domain);

            $pageComponents = [];
            for ($j = 0; $j < $components; ++$j) {
                $pageComponents[] = $this->randomComponent();
            }

            $page = $website->getIndex();
            $page->setDefinition((new PageComponent($pageComponents))->toArray());

            $this->websiteRepository->save($website);
            echo '.';
        }
        echo PHP_EOL;

        return 0;
    }

    private function randomComponent($inner = 6): AbstractComponent
    {
        $rand = rand(max(1, $inner - 3), $inner);

        switch ($rand) {
            case 1:
                return new HtmlComponent($this->randomString(500, 'abcdef ghijk lmnopq rstuv wxyz'));
            case 2:
                $c = new ImageComponent();
                $c->setImage('data:image/gif;base64,R0lGODlhEAAQAMQAAORHHOVSKudfOulrSOp3WOyDZu6QdvCchPGolfO0o/XBs/fNwfjZ0frl3/zy7////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAkAABAALAAAAAAQABAAAAVVICSOZGlCQAosJ6mu7fiyZeKqNKToQGDsM8hBADgUXoGAiqhSvp5QAnQKGIgUhwFUYLCVDFCrKUE1lBavAViFIDlTImbKC5Gm2hB0SlBCBMQiB0UjIQA7');

                return $c;
            case 3:
                $c = new BackgroundImageComponent();
                $c->setContent($this->randomComponent($inner - 1));
                $c->setBackgroundColor('#' . $this->randomString(6, '89ABCDEF'));

                return $c;
            case 4:
                return new TwoColumnsComponent(
                    $this->randomComponent($inner - 1),
                    $this->randomComponent($inner - 1)
                );
            case 5:
                $c = new MarginsComponent($this->randomComponent($inner - 1));
                $c->setMarginBottom(MarginSize::SMALL);
                $c->setMarginTop(MarginSize::SMALL);
                $c->setMarginLeftRight(MarginSize::MEDIUM);

                return $c;
            default:
                return new ThreeColumnsComponent(
                    $this->randomComponent($inner - 1),
                    $this->randomComponent($inner - 1),
                    $this->randomComponent($inner - 1)
                );
        }
    }

    private function randomString(int $length = 10, string $characters = 'abcdefghijklmnopqrstuvwxyz'): string
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
