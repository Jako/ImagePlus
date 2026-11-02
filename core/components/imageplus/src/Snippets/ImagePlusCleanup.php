<?php
/**
 * ImagePlusCleanup Snippet
 *
 * @package imageplus
 * @subpackage snippet
 */

namespace TreehillStudio\ImagePlus\Snippets;

use TreehillStudio\ImagePlus\Helper\Cleanup;
use xPDO;

/**
 * Class ImagePlusCleanup
 */
class ImagePlusCleanup extends Snippet
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
            'path' => '',
            'source::explodeSeparated' => '',
            'minAge::int' => 24,
        ];
    }

    /**
     * Execute the snippet and return the result.
     *
     * @return string
     */
    public function execute()
    {
        // The cleanup removes files, so it is restricted to sudo users and administrators
        $user = $this->modx->user;
        if (!$user || !$user->hasSessionContext('mgr') || !($user->get('sudo') || $user->isMember('Administrator'))) {
            return '';
        }

        $cleanup = new Cleanup($this->modx, $this->getProperty('dryRun'));
        $cleanup->run((string)$this->getProperty('path'), array_filter(array_map('intval', (array)$this->getProperty('source'))), max(0, (int)$this->getProperty('minAge')));

        $levels = [
            xPDO::LOG_LEVEL_ERROR => 'ERROR',
            xPDO::LOG_LEVEL_WARN => 'WARN',
            xPDO::LOG_LEVEL_INFO => 'INFO',
        ];
        $output = [];
        foreach ($cleanup->getMessages() as $message) {
            $output[] = ($levels[$message['level']] ?? 'DEBUG') . ': ' . htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8');
        }
        if (!$output) {
            $output[] = 'INFO: ' . (($this->getProperty('dryRun')) ? 'Dry run: ' : '') . 'No crop folder found.';
        }
        return '<pre>' . implode("\n", $output) . '</pre>';
    }
}
