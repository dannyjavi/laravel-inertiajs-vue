export default function formatDate(date){
    const options = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }
    const string = new Date(date)
    return string.toISOString('es-ES', options)
};