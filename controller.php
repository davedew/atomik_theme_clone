<?php
namespace Concrete\Package\AtomikThemeClone;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\File\FileList;
use Concrete\Core\File\Filesystem;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\PageList;
use Concrete\Core\Page\Stack\StackList;
use Concrete\Core\File\StorageLocation\StorageLocationFactory;
use Concrete\Core\Page\Type\Type;
use Concrete\Core\User\User;
use Concrete\Core\Http\Request;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package {

    protected $pkgHandle = 'atomik_theme_clone'; //Change to the file directory name, Example: theme_afixia
    protected $themePath = 'themes/atomik_clone/'; //Change theme path
    protected $themeName = 'Atomik Clone'; //Change Theme Name
    protected $themeHandle = 'atomik_clone'; //Change Theme Handle
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '1.0';
//    protected $pkgAllowsFullContentSwap = true; // This doesn't really work here.
    protected $pkgContentProvidesFileThumbnails = true;

    //Replace ThemeName with the name of the theme
    public function getPackageDescription() {
        return t("Adds Atomik Clone Theme."); //Example "Adds Afixia Theme"
    }

    //Name the Package
    public function getPackageName() {
        return t($this->themeName); //Example Afixia
    }

    public function getEntityManagerProvider()
    {
        return new StandardPackageProvider($this->app, $this, [
            'src/Concrete' => 'Concrete\Package\AtomikThemeClone'
        ]);
    }

    public function install() {
        $pkg = parent::install();

        $this->install_config();
        $this->install_themes();

        $r = Request::getInstance();
        $r->request->all();
        if ($r->request->get('pkgDoFullContentSwap')) {
            // install sample content.
            $this->swapContent($r->request->all());
        }
    }

    public function swapContent($options)
    {
        //pkgDoFullContentSwap
        $u = $this->app->make(User::class);
        if ($u->isSuperUser()) {
            // this can ONLY be used through the post. We will use the token to ensure that
            $valt = $this->app->make('helper/validation/token');
            if ($valt->validate('install_options_selected', $options['ccm_token'])) {
                $this->app->make('cache/request')->disable();

                $pl = new PageList();
                $pages = $pl->getResults();
                foreach ($pages as $c) {
                    $c->delete();
                }

                $fl = new FileList();
                $files = $fl->getResults();
                foreach ($files as $f) {
                    $f->delete();
                }

                // clear stacks
                $sl = new StackList();
                foreach ($sl->getResults() as $c) {
                    $c->delete();
                }

                $home = \Page::getByID(\Page::getHomePageID());
                $blocks = $home->getBlocks();
                foreach ($blocks as $b) {
                    $b->deleteBlock();
                }

                $pageTypes = Type::getList();
                foreach ($pageTypes as $ct) {
                    $ct->delete();
                }

                // Set the page type of the home page to 0, because
                // if it has a type the type will be gone since we just
                // deleted it
                $home = Page::getByID(\Page::getHomePageID());
                $home->setPageType(null);

//                $this->writeLog('content swap 2');
                $this->install_file_manager();
                $this->import_files();
                $this->move_files();
                $this->install_content();

                $this->app->make('cache/request')->enable();
            }
        }
    }

    public function install_config()
    {
        $themePaths = [
            '/account' => 'atomik_clone',
            '/members/profile' => ['atomik_clone', 'profile.php'],
        ];
        $config = $this->app->make('config');
        $config->save('app.theme_paths', $themePaths);
    }

    public function install_themes()
    {
        $ci = new ContentImporter();
        if (file_exists($this->getPackagePath() . '/themes.xml')) {
            $ci->importContentFile($this->getPackagePath() . '/themes.xml');
        }
    }

    public function install_file_manager()
    {
        $fsl = $this->app->make(StorageLocationFactory::class)->fetchDefault();

        // Create documents node in file manager
        $filesystem = new Filesystem();
        $root = $filesystem->getRootFolder();
        $documents = $filesystem->addFolder($root, 'Documents', $fsl->getID());
        $brand = $filesystem->addFolder($root, 'Brand', $fsl->getID());
        $blog = $filesystem->addFolder($root, 'Blog', $fsl->getID());
        $gallery = $filesystem->addFolder($root, 'Gallery', $fsl->getID());
        $collaboration = $filesystem->addFolder($root, 'Collaboration Slider', $fsl->getID());
        $heroes = $filesystem->addFolder($root, 'Hero Images', $fsl->getID());
        $logoSlider = $filesystem->addFolder($root, 'Logo Slider', $fsl->getID());
        $stripes = $filesystem->addFolder($root, 'Stripes', $fsl->getID());
        $team = $filesystem->addFolder($root, 'Team', $fsl->getID());
    }

    public function import_files()
    {
         if (is_dir($this->getPackagePath() . '/content_files')) {
            $ci = new ContentImporter();
            $computeThumbnails = true;
            if ($this->contentProvidesFileThumbnails()) {
                $computeThumbnails = false;
            }
            $ci->importFiles($this->getPackagePath() . '/content_files', $computeThumbnails);
        }
    }

    public function move_files() {
        $ci = new ContentImporter();

        // Now move the files
        $ci->moveFilesByName(['atomik-logo-transparent.png', 'atomik-logo.png'], 'Brand');
        $ci->moveFilesByName(['blog-01.jpg', 'blog-02.jpg', 'blog-03.jpg', 'blog-04.jpg', 'blog-05.jpg', 'blog-06.jpg'], 'Blog');
        $ci->moveFilesByName(['collaboration-01.jpg', 'collaboration-02.jpg', 'collaboration-03.jpg'], 'Collaboration Slider');
        $ci->moveFilesByName(['dummy.pdf'], 'Documents');
        $ci->moveFilesByName(['gallery-headphones.jpg', 'gallery-shoes.jpg', 'gallery-shoes2.jpg', 'gallery-skincare.jpg', 'gallery-watch.jpg', 'gallery-watch2.jpg'], 'Gallery');
        $ci->moveFilesByName(['hands-01.jpg', 'laptops-01.jpg', 'laptops-02.jpg', 'people-01.jpg', 'testimonial-01.jpg', 'testimonial-bg.jpg'], 'Stripes');
        $ci->moveFilesByName(['hero-01.jpg', 'hero-resources.jpg'], 'Hero Images');
        $ci->moveFilesByName(['logo-01.png', 'logo-02.png', 'logo-03.png', 'logo-04.png'], 'Logo Slider');
        $ci->moveFilesByName(['team-01.jpg', 'team-02.jpg', 'team-03.jpg', 'team-04.jpg', 'team-05.jpg', 'team-06.jpg'], 'Team');
    }

    public function install_content()
    {
        $ci = new ContentImporter();
        $ci->importContentFile($this->getPackagePath() . '/content.xml');
    }

}