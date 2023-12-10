import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)

const formatToDate = (date: string): string => dayjs(date).format('D MMM YYYY HH:mm')

const formatFromNow = (date: string): string => dayjs(date).fromNow()

export { formatToDate, formatFromNow }
