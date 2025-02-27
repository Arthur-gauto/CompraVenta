<!-- Default Modals -->
<button type="button" class="btn btn-primary " data-bs-toggle="modal">Standard Modal</button>
<div id="modalmantenimiento" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbltitulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_form" >
                <div class="modal-body">
                    <input type="hidden" name="cuota_id" id="cuota_id">
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Cuota pendiente</label>
                                <input type="text" class="form-control" id="cuota_pendiente" name="cuota_pagada" readonly>
                            </div>
                            <div>
                                <label for="valueInput" class="form-label">Fecha de vencimiento</label>
                                <input type="text" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" readonly>
                            </div>
                            <div>
                                <label for="valueInput" class="form-label">Pago de:</label>
                                <input type="text" class="form-control" id="cuota_pagada" name="cuota_pagada" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary ">Pagar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->