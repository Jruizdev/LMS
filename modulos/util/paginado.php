<?php

class Paginado {
    private $tam_pag;
    private $max_pags;
    private $pag_actual;
    private $max_selectores;
    private $total_registros;
    private $pagina;

    public function __construct ($pagina, $tam_pag) {

        $this->setMaxSelectores  (5);
        $this->setPagActual      (isset ($_GET ['pag']) ? $_GET ['pag'] : 1);
        $this->setTamPag         ($tam_pag);
        $this->setPagina         ($pagina);
    }

    private function calcularPaginado () {
        // Validar que la configuración del paginado sea válida
        if ($this->pag_actual < 1 || $this->max_pags < 1 || $this->max_selectores < 1) {
            return []; 
        }

        // Garantizar que la página actual no exceda el máximo de páginas
        $this->pag_actual = min ($this->pag_actual, $this->max_pags);
    
        // Calcular el rango de páginas por mostrar
        $mitad =  intval ($this->max_selectores / 2);
        $inicio = max (1, $this->pag_actual - $mitad);
        $fin = 	  min ($this->max_pags, $inicio + $this->max_selectores - 1);
    
        // Ajustar el inicio si el fin está cerca del máximo
        if ($fin - $inicio < $this->max_selectores - 1) {
            $inicio = max (1, $fin - $this->max_selectores + 1);
        }
        $paginado = [];
    
        if ($inicio > 1) {
            $paginado [] = 1;
            if ($inicio > 2) $paginado [] = '...'; 
        }
    
        // Agregar las páginas intermedias
        for ($i = $inicio; $i <= $fin; $i++) {
            $paginado [] = $i;
        }
    
        if ($fin < $this->max_pags) {
            if ($fin < $this->max_pags - 1) $paginado [] = '...';
            $paginado [] = $this->max_pags;
        }
        return $paginado;
    }

    public function setPagina ($pagina)                 { $this->pagina = $pagina; }
    public function setTamPag ($size)                   { $this->tam_pag = $size; }
    public function setMaxPags ($max_pags)              { $this->max_pags = $max_pags; }
    public function setPagActual ($pag_actual)          { $this->pag_actual = $pag_actual; }
    public function setMaxSelectores ($max_selectores)  { $this->max_selectores = $max_selectores; }
    public function getPagina ()                       { return $this->pagina; }
    public function getTotalRegistros ()               { return $this->total_registros; }
    public function getTamPag ()                       { return $this->tam_pag; }
    public function getMaxPags ()                      { return $this->max_pags; }
    public function getPagActual ()                    { return $this->pag_actual; }
    public function getMaxSelectores ()                { return $this->max_selectores; }
    public function setTotalRegistros ($total)          { 
        $agregar = (
            $total == $this->tam_pag || 
            $this->tam_pag == 1 ||
            (floatval ($total / $this->tam_pag) == intval ($total / $this->tam_pag)) &&
            $total > 0
        ) ? 0 : 1;
        $this->total_registros = $total; 
        $this->setMaxPags (intval ($this->total_registros / $this->tam_pag + $agregar));
    }
    public function crearPaginado () { 

        $paginas = $this->calcularPaginado ();
        
        foreach ($paginas as $pag) {
            if ($pag != $this->pag_actual && $pag == "...") {
                echo '<button class="pagina separador">'.$pag.'</button>';
            } 
            else if ($pag != $this->pag_actual) {
                echo '
                <button class="pagina" data-pag="'.$pag.'"
                onclick="window.location.replace (
                    \''.$this->pagina.'?pag='.$pag.'\')">'.$pag.'</button>';
            } 
            else {
                echo '<button class="pagina actual">'.$pag.'</button>';
            }
        }
    }
    public function validarPagActual () {
        if ($this->pag_actual > $this->max_pags) {
            header('Location: '.$this->pagina); die ();
        }
    }
}
?>