<?php defined('APPLICATION') or die;

$PluginInfo['HowToProfilePage'] = array(
    'Name' => 'HowTo: Profile Page',
    'Description' => 'This plugin shows how to add a custom page to the user profile.',
    'Version' => '0.1',
    'RequiredApplications' => array('Vanilla' => '>= 2.1'),
    'RequiredTheme' => false,
    'MobileFriendly' => true,
    'HasLocale' => false,
    'Author' => 'Robin Jurinka',
    'AuthorUrl' => 'http://vanillaforums.org/profile/44046/R_J',
    'License' => 'MIT'
);



/**
 * Example plugin that adds a custom page to the user table.
 *
 * If you want to show additional content in the users profile on a separate
 * page, you might like to know how to create that page. Here is an example!
 *
 * @package HowToProfilePage
 * @author Robin Jurinka
 * @license MIT
 */
class HowToProfilePagePlugin extends Gdn_Plugin {
    /**
     * This function only inserts a menu entry for our custom page.
     *
     * @param object $sender ProfileController.
     * @return void.
     * @package HowToProfilePage
     * @since 0.1
     */
    public function profileController_addProfileTabs_handler ($sender) {
        // Insert a menu entry for our page
        $sender->addProfileTab(
            t('Custom Profile Page'),
            'profile/howtoprofilepage/'.$sender->User->UserID.'/'.rawurlencode($sender->User->Name)
            // ,
            // 'HowToProfilePage'
        );
    }

    /**
     * Prepares the page that we want to display.
     *
     * @param object $sender ProfileController.
     * @return void.
     * @package HowToProfilePage
     * @since 0.1
     */
    public function profileController_howToProfilePage_create ($sender) {
        // We have to tell the profile controller whose profile we want to see.
        // The info is taken from the url - it has been passed as a parameter.
        // "true" denotes that we want to also check if the viewer as correct
        // permissions to see the profile.
        $sender->GetUserInfo('', '', $sender->RequestArgs[0], true);

        // Don't show edit profile menu.
        $sender->EditMode(false);

        // Set the breadcrumbs to match our page.
        $sender->setData(
            'Breadcrumbs',
            array(
                array('Name' => t('Profile'), 'Url' => '/profile'),
                array('Name' => t('Custom Profile Page'), 'Url' => '/profile/howtoprofilepage')
            )
        );

        // This should be the title of our page.
        $sender->setData('Title', t('Custom Profile Page'));

        // Get the view that we like to see inside the profile page. Called that
        // way, the function expects the view to be at
        // /plugins/HowToProfilePage/views.
        $customView = $this->getView('howtoprofilepage.php');

        // Link that view to our tab.
        $sender->setTabView(t('Custom Profile Page'), $customView);

        // After all we want to see a normal profile page and that's why we have
        // to fetch the standard view (index) where our page should be embedded
        // into.
        $profileView = $sender->fetchViewLocation('index', 'ProfileController', 'dashboard');

        // If you need CSS and/or JS in your custom profile page, this would be
        // the place to add them.
        // $sender->addCssFile('howtoprofilepage.css', 'plugins/HowToProfilePage');
        // $sender->addJsFile('howtoprofilepage.js', 'plugins/HowToProfilePage');

        // Create that page!
        $sender->Render($profileView);
    }
}
