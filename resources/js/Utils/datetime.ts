import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import isBetween from 'dayjs/plugin/isBetween'

dayjs.extend(relativeTime)
dayjs.extend(isBetween)

export const formatToDate = (date: string): string => dayjs(date).format('D MMM YYYY HH:mm')

export const formatFromNow = (date: string): string => dayjs(date).fromNow()

export const checkIsLastOfHours = (date: string, hour: number): boolean => dayjs(date).isBetween(dayjs().subtract(hour, 'h'), dayjs())

export const differenceByMillis = (dateA: string, dateB: string): number => dayjs(dateB).diff(dayjs(dateA), 'ms')
