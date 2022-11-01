<?php

namespace App\Utils\Enum;

class EnumForInvoice
{
    const NO_INVOICES = 'No cuenta con facturas o empresas';

    const NOT_FOUND_INVOICE = 'Factura no encontrada';

    const NOT_PERMISSIONS = 'No esta permitido realizar esta accion';

    const ERROR_DATA_INVOICE = 'Faltan datos para guardar la factura, verifique los datos enviados';

    const DELETED_INVOICE = 'Factura eliminada';
}
