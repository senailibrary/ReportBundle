# ReportBundle

The ReportBundle provides a standard template for quick creation of report form listing.
It allows you to add basic calculations columns.

## Installation

ReportBundle uses Composer, please checkout the [composer website](http://getcomposer.org) for more information.

The simple following command will install `ReportBundle` into your project. It also add a new
entry in your `composer.json` and update the `composer.lock` as well.

```bash
$ composer require senailibrary/reportbundle:dev-master
```

## Getting Started

```php
<?php

    use SenaiLibrary\ReportBundle\Component\Report;

    public function pdfAction(Request $request) {
        $report = new Report();
        $collection = "your collection"
        $detail = new \SenaiLibrary\ReportBundle\Component\Detail($collection);

        $report->setTitle('Report Title')
                ->setShowPageNumber(true)
                ->setOrientation(Report::Portrait)
                ->addDetail($detail);
        return new \Symfony\Component\HttpFoundation\Response($report->renderPdf(), 200, array(
            'Content-Type' => 'application/pdf',
                )
        );
    }
```