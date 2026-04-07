class IdleIn {

    // Entrada con tiempo de espera programado para ejecución de acción
    constructor (input, timeout_ticks, intervalo_ms, accion = null) {
        this.campo = input;
        this.tiempo_idle = 0;
        this.num_ticks = timeout_ticks;
        this.intervalo = null;
        this.intervalo_ms = intervalo_ms;
        this.accion = accion;
    }

    AsignarAccion (accion) {
        this.accion = accion;
    }

    ActualizarEspera () {
        this.tiempo_idle = 0;
    
        if (!this.intervalo) this.intervalo = setInterval (() => {
            if (this.tiempo_idle >= this.num_ticks) {
                if (this.accion) this.accion ();
                this.tiempo_idle = 0;
                clearInterval (this.intervalo);
                this.intervalo = null;
            }
            this.tiempo_idle++;
        }, this.intervalo_ms);
    }
}