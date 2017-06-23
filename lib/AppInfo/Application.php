<?php
/**
 * @copyright Copyright (c) 2017 Bjoern Schiessle <bjoern@schiessle.org>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OCA\GlobalSiteSelector\AppInfo;


use OCA\GlobalSiteSelector\GlobalSiteSelector;
use OCA\GlobalSiteSelector\Master;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\Util;

class Application extends App {

	public function __construct(array $urlParams = array()) {
		parent::__construct('globalsiteselector', $urlParams);

		$container = $this->getContainer();

		$gss = $container->query(GlobalSiteSelector::class);
		$mode = $gss->getMode();

		if ($mode === 'master') {
			$this->registerMasterHooks($container);
		}
	}

	/**
	 * register hooks for the portal if it operates as a master which redirects
	 * users to their login server
	 *
	 * @param IAppContainer $c
	 */
	private function registerMasterHooks(IAppContainer $c) {
		$master= $c->query(Master::class);
		Util::connectHook('OC_User', 'pre_login', $master, 'handleLoginRequest');
	}

}