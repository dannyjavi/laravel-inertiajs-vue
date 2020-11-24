export default function (date) {

    let string = new Date(date)

    let locale = 'en-GB'

    let newAppt = {
        start: string.toLocaleDateString(locale),
        hour: string.toLocaleTimeString(locale)
    }
    return newAppt
};