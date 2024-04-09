local key = 'test'

redis.call('SET', key, 10)

local result = redis.call('INCR', key)

return result
