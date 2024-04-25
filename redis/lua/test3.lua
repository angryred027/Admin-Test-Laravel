local result1 = redis.call('SET', KEYS[1], ARGV[1])
local result2 = redis.call('SET', KEYS[2], ARGV[2])

return {result1,result2}
