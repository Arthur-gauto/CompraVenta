<div class="modal fade" id="modalpago" tabindex="-1" role="dialog" aria-labelledby="modalpagoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpagoLabel">Registrar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pago_form">
                    <input type="hidden" id="vent_id_pago" name="vent_id">
                    <input type="hidden" id="cli_id_pago" name="cli_id">
                    <input type="hidden" id="caj_id" name="caj_id" value="10"> <!-- Ajusta según tu sistema -->
                    <div class="form-group">
                        <label for="cobro_pagado_pago">Monto a Pagar</label>
                        <input type="number" class="form-control" id="cobro_pagado_pago" name="cobro_pagado" required min="1">
                    </div>
                    <button type="submit" class="btn btn-success">Registrar Pago</button>
                </form>
            </div>
        </div>
    </div>
</div>