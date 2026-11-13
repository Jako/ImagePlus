<?php
/**
 * Abstract processor
 *
 * @package imageplus
 * @subpackage processors
 */

namespace TreehillStudio\ImagePlus\Processors;

use modMediaSource;
use modProcessor;
use modX;
use TreehillStudio\ImagePlus\ImagePlus;

/**
 * Class Processor
 */
abstract class Processor extends modProcessor
{
    public $languageTopics = ['imageplus:default'];

    /** @var ImagePlus $imageplus */
    public $imageplus;

    /** @var modMediaSource $source */
    protected $source;

    /**
     * {@inheritDoc}
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('imageplus.core_path', null, $this->modx->getOption('core_path') . 'components/imageplus/');
        $this->imageplus = $this->modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
            'core_path' => $corePath
        ]);
    }

    abstract public function process();

    /**
     * Get the media source of the request
     *
     * @return modMediaSource|null
     */
    protected function getSource()
    {
        if ($this->source) {
            return $this->source;
        }

        $id = $this->getProperty('source');
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx, $id);

        if ($this->source) {
            $this->source->getWorkingContext();
            $this->source->initialize();
            return $this->source;
        }

        return null;
    }

    /**
     * Remove the site url and the media source base url from an image path
     *
     * @param string $src
     * @return string
     */
    protected function prepareSrc($src)
    {
        $src = rawurldecode($src);
        // Prevent browsing outside the media source
        if (strpos($src, "\0") !== false || preg_match('#(^|[/\\\\])\.\.([/\\\\]|$)#', $src)) {
            return '';
        }
        $siteUrl = $this->modx->getOption('site_url');
        if (strpos($src, $siteUrl) === 0) {
            $src = substr($src, strlen($siteUrl));
        }
        if ($this->getSource()) {
            $baseUrl = ltrim($this->source->getBaseUrl(), '/');
            $src = ltrim($src, '/');
            if ($baseUrl !== '' && strpos($src, $baseUrl) === 0) {
                $src = substr($src, strlen($baseUrl));
            }
        }
        return ltrim($src, '/');
    }
}
