export function useVolunteerProgress(form) {

    const sectionProgress = (section) => {

        const map = {

            contacts: [
                form.phone,
                form.email,
                form.city
            ],

            social: [
                form.telegram,
                form.whatsapp,
                form.vk
            ],

            professional: [
                form.profession,
                form.languages
            ],

            participation: [
                form.timePerWeek,
                form.participationFormat,
                form.canTravel
            ],

            additional: [
                form.motivation,
                form.agreePersonal
            ]

        };


        const fields = map?.[section];

        if (!Array.isArray(fields)) {
            console.warn(`No fields for section: ${section}`);
            return 0;
        }

        const filled = fields.filter(v => {

            if (Array.isArray(v)) {
                return v.length > 0;
            }

            return v !== null &&
                v !== undefined &&
                v !== "" &&
                v !== false;

        }).length;

        return Math.round((filled / fields.length) * 100);

    };

    return {
        sectionProgress
    };
}
