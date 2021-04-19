<?php

namespace LeKoala\SimpleSearch;

use PageController;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;
use SilverStripe\Core\Extensible;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\ORM\FieldType\DBField;

/**
 * A search controller
 */
class SimpleSearchController extends PageController
{
    /**
     * Process and render search results.
     */
    public function index(HTTPRequest $request = null)
    {
        $request = $this->getRequest();
        $Query = $request->getVar('q');
        $SearchList = new ArrayList();
        if ($Query) {
            $FullQuery = str_replace(' ', '%', $Query);

            // Search page by title/content
            $excludedPageClasses = [
                ErrorPage::class,
            ];
            $filters =  [
                "Title:PartialMatch" => $FullQuery,
                "Content:PartialMatch" => $FullQuery,
            ];
            $Results = SiteTree::get()->filterAny($filters)->exclude('ClassName', $excludedPageClasses);
            foreach ($Results as $Result) {
                if ($Result->canView()) {
                    $SearchList->push($Result);
                }
            }

            // Also search DataObjects in sitemap
            $dataObjects = self::getSearchableDataObjects();
            foreach ($dataObjects as $dataObject) {
                $sng = singleton($dataObject);

                $filters = [];
                if ($sng->hasMethod('getSearchFilters')) {
                    // Use dedicated filters
                    $filters = $sng->getSearchFilters();
                } else {
                    // Scaffold search based on fields
                    $fields = Config::inst()->get($dataObject, 'db');
                    if (isset($fields['Title'])) {
                        $filters['Title:PartialMatch'] = $FullQuery;
                    }
                    if (isset($fields['Name'])) {
                        $filters['Name:PartialMatch'] = $FullQuery;
                    }
                    if (isset($fields['Content'])) {
                        $filters['Content:PartialMatch'] = $FullQuery;
                    }
                    if (isset($fields['Description'])) {
                        $filters['Description:PartialMatch'] = $FullQuery;
                    }
                }

                $Results = $dataObject::get()->filterAny($filters);
                if ($Results) {
                    foreach ($Results as $Result) {
                        if ($Result->canView()) {
                            $SearchList->push($Result);
                        }
                    }
                }
            }
        }

        $PaginatedList = new PaginatedList($SearchList, $request);
        $data = array(
            'Results' => $PaginatedList,
            'Query' => DBField::create_field('Text', $Query),
            'Title' => _t('SimpleSearch.SearchResults', 'Search Results'),
            'YouSearchedFor' => _t('SimpleSearch.YouSearchFor', 'You searched for %s', [$Query]),
        );
        return $this->customise($data)->renderWith(array('Page_results', 'Page'));
    }

    /**
     * Get all classes using the given extension
     *
     * @param boolean $strict
     * @return array
     */
    public static function getSearchableDataObjects($strict = false)
    {
        if (!class_exists(\Wilr\GoogleSitemaps\GoogleSitemap::class)) {
            return [];
        }
        $extension = \Wilr\GoogleSitemaps\Extensions\GoogleSitemapExtension::class;
        $classes = ClassInfo::getValidSubClasses(DataObject::class);
        $extendedClasses = [];
        foreach ($classes as $lc_class => $class) {
            if ($class === SiteTree::class || is_subclass_of($class, SiteTree::class)) {
                continue;
            }
            if (Extensible::has_extension($class, $extension, $strict)) {
                $extendedClasses[] = $class;
            }
        }
        return $extendedClasses;
    }
}
