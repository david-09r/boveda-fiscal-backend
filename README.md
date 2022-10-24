# boveda-fiscal
Boveda fiscal hecho con mis compañeros de estudio sobre un proyecto de clase.

__No nos hacemos responsable del uso inadecuado del codigo como tambien de la informacion, el repositorio es publico pero solo para uso practico y de estudio, como tambien relacionado al aprendizaje, agredecemos su comprension. El proyecto se realizo solo como metodo de aprendizaje y no como produccion real de la empresa__

__Lo informo para evitar cualquier problema sobre este proyecto.__
# Caso de estudio

Debido al auge y aceptación de la factura electrónica en Colombia, la DIAN ha decidido crear un sistema que permita almacenar las facturas electrónicas de todas las empresas del país. Este año la DIAN ha abierto una licitación pública para el desarrollo de un sitio web denominado bóveda fiscal que permita administrar sus históricos de facturas. La idea es que cada empresa esté en capacidad de enviar la información de sus facturas electrónicas al sistema y consultarlas. Adicionalmente la bóveda fiscal permitirá la auditoria y seguimiento a las empresas que emiten facturas en el país. Para la versión 1.0 de la bóveda fiscal, la información que se requiere almacenar para cada factura es la siguiente:

Información del emisor y receptor de la factura:

- Nit
- Razón Social
- Dirección de la sede principal
- Nombre del representante legal al momento de emitir la factura electrónica
- Número telefónico de contacto
- Correo electrónico
- Sitio web (opcional)

Información de la factura:

- Número de factura
- Detalle de la factura (nombre, cantidad, valor unitario, total)
- Total IVA recaudado
- Monto total a pagar
- Fecha de emisión de la factura
- Fecha de pago de la factura
- Tipo de factura (Nota débito, Nota crédito, Normal)
- Estado de la factura (Activa o Cancelada)

La bóveda fiscal debe permitir:

- A nivel de empresa contar con las siguientes vistas:
    - Una página que permita registrarse al sistema y crear un login y password para la empresa. El login es de libre elección, pero debe validarse que no exista en el sistema. En la página de registro se debe solicitar el Nit, la razón social, dirección, número
    telefónico y el correo electrónico. Se deben validar los datos de entrada.
    - Un servicio web (en REST o SOAP) que permita enviar las facturas electrónicas de una empresa a la DIAN. Notas: El servicio debe ofrecer algún mecanismo de seguridad y es necesario validar de alguna manera que cada uno de los campos de la factura contenga información, o sea no pueden llegar facturas con campos incompletos.
    - Una página que permita consultar todas sus facturas organizadas por fecha de emisión, de la más reciente a la más antigua, en este caso solo puede ver las facturas que corresponden a su usuario, la página debe usar paginación configurable en la interfaz gráfica, esto quiere decir que la persona selecciona cuantas facturas aparecerán por página.
- Para la DIAN contar con las siguientes vistas:
    - Una página que permita consultar las facturas de una empresa por medio de Nit o Razón Social (Validar los datos). Adicional se debe usarse paginación configurable
    - Una página que permita consultar el top 50 de las facturas con mayor valor emitido en el país.
