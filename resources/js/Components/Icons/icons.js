// icons.js
import * as solid from '@fortawesome/free-solid-svg-icons';

export const iconList = Object.keys(solid)
    .filter(k => k.startsWith('fa') && solid[k].iconName) // фильтруем только иконки
    .map(k => {
        const icon = solid[k];

        // Преобразуем prefix → CSS класс
        const prefixMap = {
            fas: "fa-solid",
            far: "fa-regular",
            fab: "fa-brands"
        };

        const cssPrefix = prefixMap[icon.prefix] || "fa-solid";
        const cssName = `fa-${icon.iconName}`;

        return {
            name: icon.iconName,       // hospital
            icon: icon,                // faHospital объект
            class: `${cssPrefix} ${cssName}` // fa-solid fa-hospital
        };
    });
