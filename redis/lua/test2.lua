local result1 = redis.call('GET', KEYS[1])
local result2 = redis.call('GET', KEYS[2])

return {result1,result2}
