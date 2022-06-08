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
            var btnActionUnBan = '<button title="Bloquear usuario" type="button" data-id="' +
            row.id +
            '" id="btn-disable-user" style="border-radius: 4px; padding: 4px 12px;" class="is-rows-el btn-disable btn-keys btn-danger"><i style="margin-right: 0px !important;" class="fa-solid fa-ban"></i></button>';
            if(row.is_banned) {
                btnActionUnBan = '<button title="Desbloquear usuario" type="button" data-id="' +
                row.id +
                '" id="btn-disable-user" style="border-radius: 4px; padding: 4px 12px;" class="is-rows-el btn-unban btn-keys btn-success"><i style="margin-right: 0px !important;" class="fa-solid fa-user-check"></i></button>'
            }
            return (
                '<div style="display:grid; grid-template-columns: 1fr 1fr; column-gap: 4px;">' +
                '<button title="Credenciales" type="button" data-id="' +
                row.id +
                '" style="border-radius: 4px; padding: 4px 12px;" class="is-rows-el btn-credentials btn-keys btn-primary"><i style="margin-right: 0px !important;" class="fa-solid fa-key"></i></button>' + btnActionUnBan + '</div>'
            );
        },
    },
];
