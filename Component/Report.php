<?php

namespace SenaiLibrary\ReportBundle\Component;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use HTML2PDF;
use SenaiLibrary\ReportBundle\Component\Detail;

class Report {

    const Landscape = "L";
    const Portrait = "P";

    private $twig;
    private $title;
    private $subTitle;
    private $headerLeft;
    private $headerCenter;
    private $headerRight;
    private $showPageNumber = true;
    private $orientation = Self::Portrait;
    private $details = array();
    private $charset = 'UTF-8';

    public function __construct() {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/views');
        $this->twig = new \Twig_Environment($loader);
        $this->twig->getExtension('core')->setNumberFormat(2, ',', '.');
    }

    public function renderView($template, $parameter = array()) {
        return $this->twig->loadTemplate($template)
                        ->render($parameter);
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getSubTitle() {
        return $this->subTitle;
    }

    public function setSubTitle($subTitle) {
        $this->subTitle = $subTitle;
        return $this;
    }

    public function getHeaderLeft() {
        return $this->headerLeft;
    }

    public function getHeaderCenter() {
        return $this->headerCenter;
    }

    public function setHeaderLeft($headerLeft) {
        $this->headerLeft = $headerLeft;
        return $this;
    }

    public function setHeaderCenter($headerCenter) {
        $this->headerCenter = $headerCenter;
        return $this;
    }

    public function setHeaderRight($headerRight) {
        $this->headerRight = $headerRight;
        return $this;
    }

    public function getShowPageNumber() {
        return $this->showPageNumber;
    }

    public function setShowPageNumber($showPageNumber) {
        $this->showPageNumber = $showPageNumber;
        return $this;
    }

    public function getOrientation() {
        return $this->orientation;
    }

    public function setOrientation($orientation) {
        $this->orientation = $orientation;
        return $this;
    }

    public function addDetail(Detail $detail) {
        $this->details[] = $detail;
        return $this;
    }

    public function getDetails() {
        return $this->details;
    }
    
    public function getCharset() {
        return $this->charset;
    }

    public function setCharset($charset) {
        $this->charset = $charset;
        return $this;
    }

    public function renderPdf() {
        $this->calculateDetails();
        $html = $this->renderView('report.html.twig', array('report' => $this));
        $html2pdf = new HTML2PDF('P', 'A4', 'pt', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($html);
        return $html2pdf->Output($this->title . '.pdf');
    }

    public function preview() {
        $html = $this->renderView('report.html.twig', array('report' => $this));
        return new Response($html);
    }
    
    private function calculateDetails() {
        foreach ($this->details as $detail) {
            $detail->calculateData();
        }        
    }

}
