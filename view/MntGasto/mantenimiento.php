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
                    <input type="hidden" name="gas_id" id="gas_id">
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Descripcion del Gasto</label>
                                <input type="text" class="form-control" id="gas_descrip" name="gas_descrip" required>
                            </div>
                            <div>
                                <label for="valueInput" class="form-label">Monto del Gasto</label>
                                <input type="text" class="form-control" id="gas_mon" name="gas_mon" required>
                            </div>
                            <div>
                                <label for="valueInput" class="form-label">Tipo de Gasto</label>
                                <select type="text" class="form-control form-select" name="gas_tipo" id="gas_tipo" aria-label="Seleccionar">
                                    <option selected>Seleccionar </option>
                                    <option value="FIJO">FIJO</option>
                                    <option value="VARIADO">VARIADO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary ">Guardar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->