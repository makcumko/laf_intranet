<?php
namespace App\Controller;

class AdminController extends AbstractController
{
    /**
     * @var \App\Model\Service\Users
     */
    private  $userService;

    /** @var \App\Model\Service\Pages */
    private $pageService;

    public function _prepare() {
        if (!$this->user['admin']) {
            throw new \Exception("Нет прав доступа");
        }
        $this->addBreadCrumb("Админко", "/Admin/");
        $this->tecdocService = \App\Model\Registry::Singleton("\App\Model\Service\Tecdoc");
        $this->pageService = \App\Model\Registry::Singleton("\App\Model\Service\Pages");
        $this->layout("Admin");
    }

    public function Main() {
        return "Админко, потом сделаю";
    }


    public function Pages() {
        $this->addBreadCrumb("Редактирование страниц", "/Admin/Pages");
        $this->block("main", "Admin/Pages");
        $this->bind("leftmenu", "Pages");

        return $this->pageService->getAll();
    }

    public function PageEdit($id = null) {
        $this->addBreadCrumb("Редактирование страниц", "/Admin/Pages");
        $this->bind("leftmenu", "Pages");

        if (isset($this->request->params['text'])) {
            $params = [
                "code" => $this->request->params['code'],
                "title" => $this->request->params['title'],
                "text" => $this->request->params['text']
            ];

            if ($id) {
                $this->pageService->pageGateway->update($id, $params);
            } else {
                $this->pageService->pageGateway->insert($params);
            }
            $this->redirect("/Admin/Pages");
        }

        if ($id) {
            $page = $this->pageService->getById($id);
            $this->addBreadCrumb($page['title'], "/Admin/PageEdit/".$page['id']);
        } else {
            $this->addBreadCrumb("Новая страница", "/Admin/PageEdit/");
            $page = [
                'id' => 0,
                'code' => '',
                'title' => '',
                'text' => ''
            ];
        }
        $this->block("main", "Admin/PageEdit");
        return $page;
    }



    public function Catalog() {
        $this->addBreadCrumb("Настройки отображения автомобилей в каталоге", "/Admin/Catalog");
        $this->block("main", "Admin/Tecdoc");
        $this->bind("leftmenu", "Catalog");
    }

    public function CatalogSettings() {
        $this->layout("Block");
        $this->block("main", "Admin/TecdocSettings");
        $settingsGW = \App\Model\Registry::Singleton("\App\Model\Gateway\TecdocSettings");
        if (!empty($this->request->params)) {
            foreach ($this->request->params as $key => $val) {
                $settingsGW->set($key, $val);
            }
        } else {
            return $settingsGW->settings;
        }
    }

    public function CatalogAG() {
        $this->addBreadCrumb("Настройки отображения сборочных групп", "/Admin/Catalog");
        $this->block("main", "Admin/TecdocAssemblyGroups");
        $this->bind("leftmenu", "AssemblyGroups");
    }

    public function CatalogArticles() {
        $this->addBreadCrumb("Детали", "/Admin/CatalogArticles");
        $this->block("main", "Admin/TecdocArticles");
        $this->bind("leftmenu", "Articles");
    }

    public function CatalogBrands() {
        $this->addBreadCrumb("Бренды", "/Admin/CatalogBrands");
        $this->block("main", "Admin/TecdocBrands");
        $this->bind("leftmenu", "Brands");
    }


    public function CatalogImport() {
        $this->addBreadCrumb("Загрузка товаров", "/Admin/CatalogImport");
        $this->bind("leftmenu", "Import");

        /** @var \App\Model\Service\TecdocImport */
        $tecdocImportService = \App\Model\Registry::Singleton("\App\Model\Service\TecdocImport");
        $activeImports = $tecdocImportService->getActiveImports();

        if (!empty($activeImports)) {
            $this->block("main", "Admin/CatalogImportList");
            return $activeImports;
        } elseif (!empty($_FILES)) {
//            require_once("excel_reader2.php");
//            $data = new \Spreadsheet_Excel_Reader($_FILES['import']['tmp_name']);
//            var_dump($data->dump(true,true));
//            die();

            require_once("simplexlsx.class.php");
            $xlsx = new \SimpleXLSX($_FILES['import']['tmp_name']);
            $tecdocImportService->createImport($xlsx->rows());
//            $result = $tecdocImportService->processImport();

//            $this->block("main", "Admin/CatalogImportList");
//            $this->bind("importResult", $result);
//            $activeImports = $tecdocImportService->getActiveImports();
            $this->redirect("/Admin/CatalogImportMapping");
            return $activeImports;
        } else {
            $this->block("main", "Admin/CatalogImportForm");

        }
    }

    public function CatalogImportMapping($map = null, $type = null, $skipFirst = false) {
        $this->addBreadCrumb("Загрузка товаров", "/Admin/CatalogImport");
        $this->addBreadCrumb("Распределение полей", "/Admin/CatalogImportMapping");
        $this->bind("leftmenu", "Import");

        /** @var \App\Model\Service\TecdocImport */
        $tecdocImportService = \App\Model\Registry::Singleton("\App\Model\Service\TecdocImport");
        $activeImports = $tecdocImportService->getActiveImports();

        if (empty($activeImports)) {
            $this->redirect("/Admin/CatalogImport");
        } elseif (!empty($map)) {
            $result = $tecdocImportService->processImport(explode("|", $map), $type, $skipFirst);

            $this->block("main", "Admin/CatalogImportList");
            $this->bind("importResult", $result);
            $activeImports = $tecdocImportService->getActiveImports();
            return $activeImports;
        } else {
            $this->block("main", "Admin/CatalogImportMapping");
            $this->bind("total_lines", sizeof($activeImports));
            return array_slice($activeImports, 0, 5);
        }
    }

    public function CatalogImportReset() {
        /** @var \App\Model\Service\TecdocImport */
        $tecdocImportService = \App\Model\Registry::Singleton("\App\Model\Service\TecdocImport");

        $tecdocImportService->clearActiveImports();
        $this->redirect("/Admin/CatalogImport");
    }

    public function CatalogImportRelaunch() {
        $this->addBreadCrumb("Загрузка товаров", "/Admin/CatalogImport");
        $this->bind("leftmenu", "Import");

        /** @var \App\Model\Service\TecdocImport */
        $tecdocImportService = \App\Model\Registry::Singleton("\App\Model\Service\TecdocImport");
        $result = $tecdocImportService->processImport();

        $this->block("main", "Admin/CatalogImportList");
        $this->bind("importResult", $result);
        $activeImports = $tecdocImportService->getActiveImports();
        return $activeImports;
    }
}