<?php
namespace In2code\In2publishCore\ViewHelpers\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 in2code.de
 *  Alex Kellner <alexander.kellner@in2code.de>,
 *  Oliver Eglseder <oliver.eglseder@in2code.de>
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use In2code\In2publishCore\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class GetBackendUserUsernameByIdentifierViewHelper
 */
class GetBackendUserUsernameByIdentifierViewHelper extends AbstractViewHelper
{
    /**
     * @var DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @var array
     */
    protected $cache = array();

    /**
     * Init
     *
     * @return void
     */
    public function initialize()
    {
        $this->databaseConnection = DatabaseUtility::buildLocalDatabaseConnection();
    }

    /**
     * Get username of a backend user
     *
     * @param int $identifier
     * @return string
     */
    public function render($identifier)
    {
        // lookup cache
        if (!isset($this->cache[$identifier = (int)$identifier])) {
            $result = (array)$this->databaseConnection->exec_SELECTgetSingleRow(
                'username',
                'be_users',
                'uid=' . $identifier
            );
            if (!empty($result['username'])) {
                $this->cache[$identifier] = $result['username'];
            } else {
                // fallback value
                $this->cache[$identifier] = '- [' . $identifier . ']';
            }
        }
        return $this->cache[$identifier];
    }
}
