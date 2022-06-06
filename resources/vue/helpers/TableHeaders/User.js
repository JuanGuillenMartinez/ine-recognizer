export default [
    {
        label: "ID",
        field: "id",
        width: "3%",
        sortable: true,
        isKey: true,
    },
    {
        label: "Correo electrónico",
        field: "email",
        width: "10%",
        sortable: true,
    },
    {
        label: "Nombre",
        field: "name",
        width: "10%",
        sortable: true,
    },
    {
        label: "Acciones",
        field: "quick",
        width: "5%",
        display: function (row) {
            return (
                '<button type="button" data-id="' +
                row.id +
                '" style="border-radius: 4px; padding: 4px 12px;" class="is-rows-el btn-credentials btn-keys btn-dark"><i style="margin-right: 0px !important;" class="fa-solid fa-key"></i></button>'
            );
        },
    },
];
