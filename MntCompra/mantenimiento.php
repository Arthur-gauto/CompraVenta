<!-- Modal -->
<div id="modalmantenimiento" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de Precios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="mantenimiento_form">
                <div class="modal-body">
                    
                    
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <label for="prod_pcompra" class="form-label">Precio de Compra</label>
                            <input type="number" class="form-control" id="prod_pcompram" name="prod_pcompram" required readonly>
                        </div>
                    </div>

                    <hr>

                    <!-- Tabla de listas de precios -->
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Porcentaje de Aumento</th>
                                <th>Precio Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="number" class="form-control porcentaje-input" id="porcentaje_A" step="0.01" value="50">
                                </td>
                                <td>
                                    <input type="number" class="form-control precio-input" id="precio_A" step="0.01">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="number" class="form-control porcentaje-input" id="porcentaje_B" step="0.01" value="30">
                                </td>
                                <td>
                                    <input type="number" class="form-control precio-input" id="precio_B" step="0.01">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="number" class="form-control porcentaje-input" id="porcentaje_C" step="0.01" value="20">
                                </td>
                                <td>
                                    <input type="number" class="form-control precio-input" id="precio_C" step="0.01">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>