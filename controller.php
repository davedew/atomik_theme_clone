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
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\File\Image\Thumbnail\Type\Type;
use Concrete\Core\Entity\File\Image\Thumbnail\Type\Type as ThumbnailType;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package {

    protected $pkgHandle = 'atomik_theme_clone'; //Change to the file directory name, Example: theme_afixia
    protected $themePath = 'themes/atomik_clone/'; //Change theme path
    protected $themeName = 'Atomik Clone'; //Change Theme Name
    protected $themeHandle = 'atomik_clone'; //Change Theme Handle
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '1.0';
    protected $pkgAllowsFullContentSwap = true;
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
        Theme::add($this->themeHandle, $pkg);

        $this->install_config();
        $this->install_themes();

//        $xs = Type::getByHandle('xs');
//        if (!is_object($xs)) {
//            $type = new ThumbnailType();
//            $type->setName('Extra Small');
//            $type->setHandle('xs');
//            $type->setWidth(540);
//            $type->save();
//        }
//
//        $sm = Type::getByHandle('sm');
//        if (!is_object($sm)) {
//            $type = new ThumbnailType();
//            $type->setName('Small');
//            $type->setHandle('sm');
//            $type->setWidth(540);
//            $type->save();
//        }
//
//        $md = Type::getByHandle('md');
//        if (!is_object($md)) {
//            $type = new ThumbnailType();
//            $type->setName('Medium');
//            $type->setHandle('md');
//            $type->setWidth(720);
//            $type->save();
//        }
//
//        $lg = Type::getByHandle('lg');
//        if (!is_object($lg)) {
//            $type = new ThumbnailType();
//            $type->setName('Large');
//            $type->setHandle('lg');
//            $type->setWidth(960);
//            $type->save();
//        }
//
//        $xl = Type::getByHandle('xl');
//        if (!is_object($xl)) {
//            $type = new ThumbnailType();
//            $type->setName('Extra Large');
//            $type->setHandle('xl');
//            $type->setWidth(1140);
//            $type->save();
//        }

//        $resource_list_entry = Type::getByHandle('resource_list_entry');
//        if (!is_object($resource_list_entry)) {
//            $type = new ThumbnailType();
//            $type->setName('Resource List Entry');
//            $type->setHandle('resource_list_entry');
//            $type->setWidth(510);
//            $type->setHeight(510);
//            $type->setSizingMode($type::RESIZE_PROPORTIONAL);
//            $type->save();
//        }
//
//        $testimonial_circle = Type::getByHandle('testimonial_circle');
//        if (!is_object($testimonial_circle)) {
//            $type = new ThumbnailType();
//            $type->setName('Testimonial Circle');
//            $type->setHandle('testimonial_circle');
//            $type->setWidth(180);
//            $type->setHeight(180);
//            $type->setSizingMode($type::RESIZE_EXACT);
//            $type->save();
//        }
//
//        $stripe_column = Type::getByHandle('stripe_column');
//        if (!is_object($stripe_column)) {
//            $type = new ThumbnailType();
//            $type->setName('Stripe Column Image');
//            $type->setHandle('stripe_column');
//            $type->setWidth(850);
//            $type->setHeight(650);
//            $type->setSizingMode($type::RESIZE_EXACT);
//            $type->save();
//        }
//
//        $atomik_gallery = Type::getByHandle('atomik_gallery');
//        if (!is_object($atomik_gallery)) {
//            $type = new ThumbnailType();
//            $type->setName('Gallery');
//            $type->setHandle('atomik_gallery');
//            $type->setWidth(860);
//            $type->setHeight(614);
//            $type->setSizingMode($type::RESIZE_EXACT);
//            $type->save();
//        }
//
//        $blog_entry_thumbnail = Type::getByHandle('blog_entry_thumbnail');
//        if (!is_object($blog_entry_thumbnail)) {
//            $type = new ThumbnailType();
//            $type->setName('Blog Entry Thumbnail');
//            $type->setHandle('blog_entry_thumbnail');
//            $type->setWidth(660);
//            $type->setHeight(428);
//            $type->setSizingMode($type::RESIZE_EXACT);
//            $type->save();
//        }
    }

    public function swapContent($options)
    {
        if ($this->validateClearSiteContents($options)) {
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

            $sl = new StackList();
            foreach ($sl->getResults() as $c) {
                $c->delete();
            }

            $home = Page::getByID(1);
            $blocks = $home->getBlocks();
            foreach ($blocks as $b) {
                $b->deleteBlock();
            }

            $pageTypes = PageType::getList();
            foreach ($pageTypes as $ct) {
                $ct->delete();
            }

            $this->install_file_manager();
            $this->import_files();
            $this->move_files();
            $this->install_content();

            $this->app->make('cache/request')->enable();
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
        // Create documents node in file manager
        $filesystem = new Filesystem();
        $root = $filesystem->getRootFolder();
        $documents = $filesystem->addFolder($root, 'Documents');
        $brand = $filesystem->addFolder($root, 'Brand');
        $blog = $filesystem->addFolder($root, 'Blog');
        $gallery = $filesystem->addFolder($root, 'Gallery');
        $collaboration = $filesystem->addFolder($root, 'Collaboration Slider');
        $heroes = $filesystem->addFolder($root, 'Hero Images');
        $logoSlider = $filesystem->addFolder($root, 'Logo Slider');
        $stripes = $filesystem->addFolder($root, 'Stripes');
        $team = $filesystem->addFolder($root, 'Team');
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