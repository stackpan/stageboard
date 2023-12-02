import React from 'react'

interface Props {
  cardCount: number
}

export default function ColumnCardSkeleton ({ cardCount }: Props): JSX.Element {
  const TaskCardSkeletons = (): JSX.Element => {
    const elm = []

    for (let i = 0; i < cardCount; i++) {
      elm.push(
        <div key={i} className="card card-compact bg-base-100 shadow-md border">
          <div className="card-body space-y-2 !p-2">
            <header className="flex justify-between">
              <div className="space-x-2 flex">
                <div className="skeleton h-6 w-6"></div>
                <div className="skeleton h-6 w-6"></div>
              </div>
              <div className="skeleton h-6 w-6"></div>
            </header>
            <div className="space-y-2">
              <div className="skeleton h-6 w-48"></div>
              <div className="skeleton h-6 w-24"></div>
            </div>
          </div>
        </div>
      )
    }

    return <>{elm}</>
  }

  return (
    <div className="card card-compact w-72 bg-base-100 shadow-md border rounded space-y-4">
      <div className="card-body">
        <div className="flex justify-between items-start">
          <div className="skeleton h-6 w-28"></div>
        </div>
        <div className="flex flex-col gap-4">
          <TaskCardSkeletons />
        </div>
      </div>
    </div>
  )
}
