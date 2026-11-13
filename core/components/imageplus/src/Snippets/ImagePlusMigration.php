<?php
/**
 * ImagePlusMigration Snippet
 *
 * @package imageplus
 * @subpackage snippet
 */

namespace TreehillStudio\ImagePlus\Snippets;

use TreehillStudio\ImagePlus\Helper\Migration;
use xPDO;

/**
 * Class ImagePlusMigration
 */
class ImagePlusMigration extends Snippet
{
    /**
     * Get default snippet properties.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            // An empty value keeps the dry run
            'dryRun::bool' => true,
        ];
    }

    /**
     * Execute the snippet and return the result.
     *
     * @return string
     */
    public function execute()
    {
        // The migration changes template variables and settings, so it is restricted to sudo users and administrators
        $user = $this->modx->user;
        if (!$user || !$user->hasSessionContext('mgr') || !($user->get('sudo') || $user->isMember('Administrator'))) {
            return '';
        }

        $migration = new Migration($this->modx, $this->getProperty('dryRun'));
        $migration->run();

        $levels = [
            xPDO::LOG_LEVEL_ERROR => 'ERROR',
            xPDO::LOG_LEVEL_WARN => 'WARN',
            xPDO::LOG_LEVEL_INFO => 'INFO',
        ];
        $output = [];
        foreach ($migration->getMessages() as $message) {
            $output[] = ($levels[$message['level']] ?? 'DEBUG') . ': ' . htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8');
        }
        if (!$output) {
            $output[] = 'INFO: ' . (($this->getProperty('dryRun')) ? 'Dry run: ' : '') . 'Nothing to migrate.';
        }
        return '<pre>' . implode("\n", $output) . '</pre>';
    }
}
