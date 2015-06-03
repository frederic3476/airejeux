<?php

namespace Applisun\AireJeuxBundle\Highmap;

use Ob\HighchartsBundle\Highcharts\AbstractChart;
use Ob\HighchartsBundle\Highcharts\ChartInterface;

class Highmap extends AbstractChart implements ChartInterface
{
    public $mapMavigation;
    public $colorAxis;
    
    /**
     * @return string
     */
    protected function renderColorAxis()
    {
        if (gettype($this->colorAxis) === 'array') {
            return $this->renderWithJavascriptCallback($this->colorAxis, "colorAxis");
        } elseif (gettype($this->colorAxis) === 'object') {
            return $this->renderWithJavascriptCallback($this->colorAxis->colorAxis, "colorAxis");
        }

        return "";
    }
    
    /**
     * @return string
     */
    protected function renderMapNavigation()
    {
        if (gettype($this->mapNavigation) === 'array') {
            return $this->renderWithJavascriptCallback($this->mapNavigation, "mapNavigation");
        } elseif (gettype($this->mapNavigation) === 'object') {
            return $this->renderWithJavascriptCallback($this->mapNavigation->mapNavigation, "mapNavigation");
        }

        return "";
    }
    
    /**
     * @return string
     */
    protected function renderNavigation()
    {
        if (gettype($this->navigation) === 'array') {
            return $this->renderWithJavascriptCallback($this->navigation, "navigation");
        } elseif (gettype($this->navigation) === 'object') {
            return $this->renderWithJavascriptCallback($this->navigation->navigation, "navigation");
        }

        return "";
    }
    
    /**
     * @return string
     */
    protected function renderPlotOptions()
    {
        if (gettype($this->plotOptions) === 'array') {            
            return $this->renderWithJavascriptCallback($this->plotOptions, "plotOptions");
        } elseif (gettype($this->plotOptions) === 'object') {            
            return $this->renderWithJavascriptCallback($this->plotOptions->plotOptions, "plotOptions");
        }

        return "";
    }
    
    /**
     * @param string $engine
     *
     * @return string
     */
    public function render($engine = 'jquery')
    {
        $chartJS = "";
        $chartJS .= $this->renderEngine($engine);
        $chartJS .= $this->renderOptions();
        $chartJS .= "\n    $('#" . (isset($this->chart->renderTo) ? $this->chart->renderTo : 'map') . "').highcharts('Map',{\n";

        // Chart
        $chartJS .= $this->renderWithJavascriptCallback($this->chart->chart, "chart");

        // Colors
        $chartJS .= $this->renderColors();

        // Credits
        $chartJS .= $this->renderCredits();

        // Exporting
        $chartJS .= $this->renderWithJavascriptCallback($this->exporting->exporting, "exporting");

        // Labels

        // Legend
        $chartJS .= $this->renderWithJavascriptCallback($this->legend->legend, "legend");

        // Loading
        // colorAxis
        $chartJS .= $this->renderColorAxis();
        
        // Loading
        // MapNavigation
        $chartJS .= $this->renderMapNavigation();
        
        // Loading
        // PlotOptions
        $chartJS .= $this->renderPlotOptions();
        
        // Loading
        // Navigation
        $chartJS .= $this->renderNavigation();
        // Pane
        $chartJS .= $this->renderPane();

        // Series
        $chartJS .= $this->renderWithJavascriptCallback($this->series, "series");

        // Scrollbar
        $chartJS .= $this->renderScrollbar();

        // Drilldown
        $chartJS .= $this->renderDrilldown();

        // Subtitle
        $chartJS .= $this->renderSubtitle();

        // Symbols

        // Title
        $chartJS .= $this->renderTitle();

        // Tooltip
        $chartJS .= $this->renderWithJavascriptCallback($this->tooltip->tooltip, "tooltip");

        // xAxis
        $chartJS .= $this->renderXAxis();

        // yAxis
        $chartJS .= $this->renderYAxis();

        // trim last trailing comma and close parenthesis
        $chartJS = rtrim($chartJS, ",\n") . "\n    });\n";

        if ($engine != false) {
            $chartJS .= "});\n";
        }

        return trim($chartJS);
    }

    /**
     * @return string
     */
    private function renderPane()
    {
        if (get_object_vars($this->pane->pane)) {
            return "pane: " . json_encode($this->pane->pane) . ",\n";
        }

        return "";
    }

    /**
     * @return string
     */
    private function renderDrilldown()
    {
        if (get_object_vars($this->drilldown->drilldown)) {
            return "drilldown: " . json_encode($this->drilldown->drilldown) . ",\n";
        }

        return "";
    }
    
    
}