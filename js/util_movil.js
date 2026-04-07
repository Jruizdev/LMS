function dispositivoMovil () {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

function descargarPDF (base64Data, fileName = 'documento.pdf') {
    
    // Crear un enlace temporal
    const link = document.createElement ('a');
    
    // Crear un Blob a partir de la cadena base64 (decodificarla)
    const byteCharacters =  atob(base64Data);
    const byteNumbers =     new Array(byteCharacters.length);

    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt (i);
    }
    const byteArray = new Uint8Array (byteNumbers);
    const blob =      new Blob ([byteArray], { type: 'application/pdf' });

    // Crear una URL del blob
    const blobUrl = URL.createObjectURL (blob);

    // Configurar el enlace para la descarga
    link.href = blobUrl;
    link.download = fileName;

    // Para dispositivos móviles, abrir en una nueva pestaña si no permite descargar
    if (navigator.userAgent.match (/Android|iPhone|iPad|iPod/i)) {
        window.open (blobUrl, '_blank');
    } 
    else {
        document.body.appendChild (link);
        link.click ();
        document.body.removeChild (link);
    }

    // Liberar la URL del blob después de 100 ms
    setTimeout(() => {
        URL.revokeObjectURL (blobUrl);
    }, 100);
}