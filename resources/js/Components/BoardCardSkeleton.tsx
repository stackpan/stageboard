import React from 'react'

export default function BoardCardSkeleton (): JSX.Element {
  return (
    <div className="card card-compact w-64 bg-base-100 shadow-md">
      <div className="skeleton h-32"/>
      <div className="card-body">
        <div className="flex justify-between">
            <div className="skeleton w-32 h-8"></div>
        </div>
        <div className="skeleton w-16 h-6"></div>
      </div>
    </div>
  )
}
