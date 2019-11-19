<?php

namespace App\Controller;

use Nexy\Slack\Client;
use App\Service\MarkdownHelper;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;


class ArticleController extends AbstractController
{
    /**
     * Currently unused: just showing a controller with a constructor
     */
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug= $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper, Client $slack)
    {
        if ($slug == 'Khaaan')
        {
            $message = $slack->createMessage()
                ->from('Khan')
                ->withIcon(':ghost:')
                ->setText('Ah, Kirk, my old friend...')
            ;

            $slack->sendMessage($message);
        }
        
        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        $articleContent = <<<EOF
        Spicy **jalapeno** bacon ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha.       

        Dolore reprehenderitlabore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cowturkey shank eu pork belly meatball non cupim.Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. **Pariaturlaboris** 

        sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicingpicanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididuntoccaecat lorem meatball prosciutto 
        quis strip steak.Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham 

        hock pork hamburger enim strip steakmollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignonstrip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consecteturcow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuckfugiat.
        EOF;

        // dump($cache);die;
        // dump($markdown);die;

        $articleContent = $markdownHelper->parse($articleContent);

        return $this->render('article/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'articleContent' => $articleContent,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {
        // TODO - actually heart/unheart the article!

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}
